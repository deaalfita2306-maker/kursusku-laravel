<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk penyimpanan data kursus baru.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:20|unique:courses,code',
            'name' => 'required|string|min:3|max:120',
            'description' => 'nullable|string|max:2000',
            'fee' => 'required|integer|min:0',
            'quota' => 'required|integer|between:1,500',
            'start_date' => 'required|date',
            'status' => 'required|in:draft,open,closed',
        ];
    }

    /**
     * Pesan kustom dalam bahasa Indonesia untuk setiap kegagalan validasi.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Kode kursus wajib diisi.',
            'code.string' => 'Kode kursus harus berupa teks.',
            'code.max' => 'Kode kursus maksimal 20 karakter.',
            'code.unique' => 'Kode kursus sudah terdaftar, silakan gunakan kode lain.',
            'name.required' => 'Nama kursus wajib diisi.',
            'name.string' => 'Nama kursus harus berupa teks.',
            'name.min' => 'Nama kursus minimal 3 karakter.',
            'name.max' => 'Nama kursus maksimal 120 karakter.',
            'description.string' => 'Deskripsi kursus harus berupa teks.',
            'description.max' => 'Deskripsi kursus maksimal 2000 karakter.',
            'fee.required' => 'Biaya kursus wajib diisi.',
            'fee.integer' => 'Biaya kursus harus berupa angka bulat.',
            'fee.min' => 'Biaya kursus minimal 0 (tidak boleh bernilai negatif).',
            'quota.required' => 'Kuota peserta wajib diisi.',
            'quota.integer' => 'Kuota peserta harus berupa angka bulat.',
            'quota.between' => 'Kuota peserta harus berada dalam rentang 1 sampai 500.',
            'start_date.required' => 'Tanggal mulai kursus wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'status.required' => 'Status kursus wajib dipilih.',
            'status.in' => 'Status kursus harus berupa salah satu dari: draft, open, closed.',
        ];
    }
}
