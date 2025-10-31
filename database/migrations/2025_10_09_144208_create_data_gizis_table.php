    <?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void {
            Schema::create('data_gizis', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');

                $table->string('desa_kelurahan', 100);
                $table->decimal('berat', 5, 2);     // Total 5 digit, 2 di belakang koma (mis: 10.50 kg)
                $table->decimal('tinggi', 5, 2);    // Total 5 digit, 2 di belakang koma (mis: 70.25 cm)

                // Kolom BB/U (Berat Badan menurut Umur)
                $table->string('status_bb_u', 50)->nullable(); // Status gizi
                $table->decimal('zs_bb_u', 4, 2)->nullable();  // Z-Score, mis: -2.15

                // Kolom TB/U (Tinggi Badan menurut Umur)
                $table->string('status_tb_u', 50)->nullable(); // Status tinggi (stunted, dll)
                $table->decimal('zs_tb_u', 4, 2)->nullable();

                // Kolom BB/TB (Berat Badan menurut Tinggi Badan)
                $table->string('status_bb_tb', 50)->nullable(); // Status gizi (wasted, dll)
                $table->decimal('zs_bb_tb', 4, 2)->nullable();

                $table->string('status')->default('pending'); // 'pending', 'approved', 'published'
                $table->boolean('is_published')->default(false);
                $table->timestamps();
            });
        }
        public function down(): void {
            Schema::dropIfExists('data_gizis');
        }
    };