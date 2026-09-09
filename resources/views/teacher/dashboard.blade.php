@extends('layouts.app')

@section('content')
<div class="space-y-8">
    
    <!-- Teacher Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-3xl p-8 text-white shadow-xl space-y-2">
        <span class="bg-white/20 text-white font-extrabold text-xs px-3 py-1 rounded-full uppercase">Dashboard Guru & Pengajar</span>
        <h1 class="text-3xl sm:text-4xl font-black">Monitoring Perkembangan Siswa 📊</h1>
        <p class="text-emerald-100 font-semibold text-base">Pantau hasil latihan, grafik akurasi, dan topik yang memerlukan bimbingan tambahan.</p>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Total Siswa Terdaftar</span>
            <div class="text-3xl font-black text-slate-800">{{ $totalStudents }} Siswa</div>
        </div>
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Rata-Rata Skor Kelas</span>
            <div class="text-3xl font-black text-amber-600">{{ round($avgScore) }} Poin</div>
        </div>
        <div class="bg-white p-6 rounded-3xl border-2 border-slate-200 shadow-sm space-y-1">
            <span class="text-slate-400 font-extrabold text-xs uppercase">Akurasi Jawaban Keseluruhan</span>
            <div class="text-3xl font-black text-emerald-600">{{ $overallAccuracy }}%</div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-3xl border-2 border-slate-200 p-6 shadow-sm space-y-4">
        <h2 class="text-xl font-black text-slate-800">Daftar Progress Siswa SD 🎒</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm font-semibold">
                <thead>
                    <tr class="border-b-2 border-slate-100 text-slate-400 font-black text-xs uppercase">
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Total Poin</th>
                        <th class="py-3 px-4">Level</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @foreach($students as $student)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-extrabold text-slate-900">{{ $student->name }}</td>
                            <td class="py-3.5 px-4">Kelas {{ $student->kelas }} SD</td>
                            <td class="py-3.5 px-4 font-black text-amber-600">{{ $student->total_score }} Poin</td>
                            <td class="py-3.5 px-4">
                                <span class="bg-amber-100 text-amber-900 font-extrabold text-xs px-2.5 py-1 rounded-full">
                                    Level {{ $student->level }} ({{ $student->level_name }})
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('guru.students.show', $student) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-black px-4 py-2 rounded-xl text-xs shadow-sm">
                                    Detail Progress ➡️
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
