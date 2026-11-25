// database/migrations/xxxx_create_courses_table.php
public function up()
{
    Schema::create('courses', function (Blueprint $table) {
        $table->id();
        $table->string('code', 10)->unique();        // DB-01, WEB-07, dll
        $table->string('name');                       // Nama Kursus
        $table->text('description')->nullable();      // Deskripsi singkat
        $table->decimal('price', 10, 2);              // Biaya
        $table->integer('quota');                     // Kuota peserta
        $table->date('start_date');                   // Tanggal Mulai
        $table->enum('status', ['Open', 'Closed', 'Draft'])->default('Draft');
        $table->timestamps();
    });
}