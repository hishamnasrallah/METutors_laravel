    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateCountriesTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('nameAR')->nullable();
                $table->string('iso3')->nullable();
                $table->string('iso2')->nullable();
                $table->string('phonecode')->nullable();
                $table->string('capital')->nullable();
                $table->string('currency')->nullable();
                $table->string('currency_symbol')->nullable();
                $table->string('tld')->nullable();
                $table->string('native')->nullable();
                $table->string('region')->nullable();
                $table->string('subregion')->nullable();
                $table->text('timezones')->nullable();
                $table->text('translations')->nullable();
                $table->decimal('latitude')->nullable();
                $table->decimal('longitude')->nullable();
                $table->string('emoji')->nullable();
                $table->string('emojiU')->nullable();
                $table->integer('flag')->nullable();
                $table->string('wikiDataId')->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('countries');
        }
    }
