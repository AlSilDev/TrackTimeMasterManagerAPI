<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UsersSeeder extends Seeder
{
    private $photoPath = 'public/fotos';
    private $typesOfUsersDesc =  ['Administrador', 'Secretariado'];
    private $typesOfUsers =             ['A', 'S'];

// TESTING SEEDER
//    private $numberOfUsers =            [4,    10,    10,   20];
//    private $numberOfFixedUsers =       [2,    3,     3,    5];
//    private $numberOfSoftDeletedUsers = [1,    4,     4,    4];

    private $numberOfUsers =            [4,    10];
    private $numberOfFixedUsers =       [2,    3];
    private $numberOfSoftDeletedUsers = [1,    4];
    private $files_M = [];
    private $files_F = [];
    static public $allUsers = [];
    public static $used_emails = [];

    public function run()
    {
        $this->command->table(['Users table seeder notice'], [
            ['Photos will be stored on path ' . storage_path('app/' . $this->photoPath)]
        ]);

        $this->limparFicheirosFotos();
        $this->preencherNomesFicheirosFotos();

        $faker = \Faker\Factory::create('pt_PT');


        for ($typeIdx = 0; $typeIdx < count($this->typesOfUsers); $typeIdx++) {
            $userType = $this->typesOfUsers[$typeIdx];
            $totalUsersOfType = $this->numberOfUsers[$typeIdx];
            $totalFixedUsersOfType = $this->numberOfFixedUsers[$typeIdx];
            $totalSoftDeletes = $this->numberOfSoftDeletedUsers[$typeIdx];

            for ($i = 1; $i <= $totalUsersOfType; $i++) {
                $userNumber = $i <= $totalFixedUsersOfType ? $i : 0;
                $userRow = $this->newFakerUser($faker, $userType, $userNumber);
                $userInfo = $this->insertUser($faker, $userRow);
                $this->command->info("Created User '{$this->typesOfUsersDesc[$typeIdx]}' - $i / $totalUsersOfType");
                if ($userType <> 'C') {
                    $this->updateFoto($userInfo);
                }
            }
            $this->softdeletes($userType, $totalSoftDeletes);
            $this->command->info("Soft deleted $totalSoftDeletes users of type '$userType'");
        }
        $this->updateRandomFotos();
    }

    private function limparFicheirosFotos()
    {
        Storage::deleteDirectory($this->photoPath);
        Storage::makeDirectory($this->photoPath);
    }

    private function preencherNomesFicheirosFotos()
    {
        $allFiles = collect(File::files(database_path('seeders/profile_photos')));
        foreach ($allFiles as $f) {
            if (strpos($f->getPathname(), 'M_')) {
                $this->files_M[] = $f->getPathname();
            } else {
                $this->files_F[] = $f->getPathname();
            }
        }
    }

    private function newFakerUser($faker, $tipo = 'C', $userByNumber = 0)
    {
        $fullname = "";
        $email = "";
        $gender = "";
        if ($userByNumber > 0) {
            switch ($tipo) {
                case 'A':
                    $fullname = "Admin " . $userByNumber;
                    $email = 'admin_' . $userByNumber . '@mail.pt';
                    break;
                case 'S':
                    $fullname = "Secretariado " . $userByNumber;
                    $email = 'sec_' . $userByNumber . '@mail.pt';
                    break;
            }
        } else {
            static::randomName($faker, $gender, $fullname, $email);
        }

        $createdAt = $faker->dateTimeBetween('-10 years', '-3 months');
        $email_verified_at = $faker->dateTimeBetween($createdAt, '-2 months');
        $updatedAt = $faker->dateTimeBetween($email_verified_at, '-1 months');

        return [
            'name' => $fullname,
            'email' =>  $email,
            'password' => bcrypt('123'),
            'remember_token' => $faker->asciify('**********'), //str_random(10),
            'created_at' => $createdAt,
            'updated_at' => $updatedAt,
            'deleted_at' => null,
            'type' => $tipo,
            'blocked' => false,
            'photo_url' => null,
            'gender' => $gender,
        ];
    }

    private function insertUser($faker, $user)
    {
        $userInfo = new \ArrayObject($user);
        $gender = $user['gender'];
        unset($user['gender']);
        $newId = DB::table('users')->insertGetId($user);
        $userInfo['id'] = $newId;
        $userInfo['gender'] = $gender;

        UsersSeeder::$allUsers[$newId] = $userInfo;

        return $userInfo;
    }

    private function gravarFoto($id, $file)
    {
        $targetDir = storage_path('app/' . $this->photoPath);
        //$sourceDir = database_path('seeds/fotos');
        $newfilename = $id . "_" . uniqid() . '.jpg';
        File::copy($file, $targetDir . '/' . $newfilename);
        DB::table('users')->where('id', $id)->update(['photo_url' => $newfilename]);
        $this->command->info("Updated Photo of User $id. File $file copied as $newfilename");
    }

    private function updateFoto($userInfo)
    {
        $fileName = null;
        if ($userInfo['gender'] == 'male') {
            if (count($this->files_M)) {
                $fileName = array_shift($this->files_M);
            }
        } else {
            if (count($this->files_F)) {
                $fileName = array_shift($this->files_F);
            }
        }
        if ($fileName) {
            $this->gravarFoto($userInfo['id'], $fileName);
        }
        return $fileName;
    }

    private function updateRandomFotos()
    {
        $ids = DB::table('users')->whereNull('photo_url')->pluck('id')->toArray();
        while (count($ids) && (count($this->files_F) || count($this->files_M))) {
            shuffle($ids);
            $this->updateFoto(UsersSeeder::$allUsers[array_shift($ids)]);
        }
    }

    private function softdeletes($userType, $totalSoftDeletes)
    {
        $ids = DB::table('users')->whereNot('email', 'like', '%\_%')->where('type', $userType)->pluck('id')->toArray();
        var_dump($ids);
        while ($totalSoftDeletes) {
            shuffle($ids);
            $userInfo = UsersSeeder::$allUsers[array_shift($ids)];
            DB::update('update users set deleted_at = updated_at, blocked=1 where id = ?', [$userInfo['id']]);
            $totalSoftDeletes--;
        }
    }


    private static function stripAccents($stripAccents)
    {
        $from = 'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ';
        $to =   'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY';
        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        return strtr($stripAccents, $mapping);
    }

    public static function randomName($faker, &$gender, &$fullname, &$email)
    {
        $gender = $faker->randomElement(['male', 'female']);
        $firstname = $faker->firstName($gender);
        $lastname = $faker->lastName();
        $secondname = $faker->numberBetween(1, 3) == 2 ? "" : " " . $faker->firstName($gender);
        $number_middlenames = $faker->numberBetween(1, 6);
        $number_middlenames = $number_middlenames == 1 ? 0 : ($number_middlenames >= 5 ? $number_middlenames - 3 : 1);
        $middlenames = "";
        for ($i = 0; $i < $number_middlenames; $i++) {
            $middlenames .= " " . $faker->lastName();
        }
        $fullname = $firstname . $secondname . $middlenames . " " . $lastname;
        $email = strtolower(UsersSeeder::stripAccents($firstname) . "." . UsersSeeder::stripAccents($lastname) . "@mail.pt");
        $i = 2;
        while (in_array($email, UsersSeeder::$used_emails)) {
            $email = strtolower(UsersSeeder::stripAccents($firstname) . "." . UsersSeeder::stripAccents($lastname) . "." . $i . "@mail.pt");
            $i++;
        }
        UsersSeeder::$used_emails[] = $email;
        $gender = $gender == 'male' ? 'M' : 'F';
    }

}
