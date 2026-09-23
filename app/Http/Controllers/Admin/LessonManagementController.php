<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonManagementController extends Controller
{
    public function index(Request $request)
    {
        $topicFilter = $request->query('topic_id');

        $query = Lesson::with('topic')->orderBy('topic_id')->orderBy('order');

        if ($topicFilter) {
            $query->where('topic_id', $topicFilter);
        }

        $lessons = $query->paginate(20);
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();

        return view('admin.lessons.index', compact('lessons', 'topics', 'topicFilter'));
    }

    public function create()
    {
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();

        return view('admin.lessons.create', compact('topics'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'video_url' => ['nullable', 'url'],
            'order' => ['required', 'integer', 'min:0'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'estimated_minutes' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        $data['slug'] = Str::slug($data['title']).'-'.Str::random(5);
        $data['is_active'] = $request->boolean('is_active', true);
        Lesson::create($data);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Materi berhasil ditambahkan! ✅');
    }

    public function edit(Lesson $lesson)
    {
        $topics = Topic::where('is_active', true)->orderBy('kelas_level')->orderBy('order')->get();

        return view('admin.lessons.edit', compact('lesson', 'topics'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->validate([
            'topic_id' => ['required', 'exists:topics,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'video_url' => ['nullable', 'url'],
            'order' => ['required', 'integer', 'min:0'],
            'difficulty' => ['required', 'in:mudah,sedang,sulit'],
            'estimated_minutes' => ['required', 'integer', 'min:1'],
            'is_active' => ['boolean'],
        ]);

        // Update slug if title changed
        if ($data['title'] !== $lesson->title) {
            $data['slug'] = Str::slug($data['title']).'-'.Str::random(5);
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $lesson->update($data);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Materi berhasil diperbarui! ✅');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Materi berhasil dihapus.');
    }
}
