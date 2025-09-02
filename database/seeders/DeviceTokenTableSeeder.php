<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DeviceTokenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('device_tokens')->delete();
        $user =User::findOrFail(2);

        $user->device_tokens()->create([
            'token'=>"esYfJ-KPSU6b4reKTq1i3J:APA91bFqdFXycmdYTXFE-Z58dCBmxVPAUUo87JEmu47wayu_vrT2Ul6pKm_6ftBV7L3O608aTOpeIdETdHGnyFwzd0ihlDGOfTnVcUFiVPVzWGgH4Z-_8Yc"
        ]);

        $user1 =User::findOrFail(3);

        $user1->device_tokens()->create([
            'token'=>"esYfJ-KPSU6b4reKTq1i3J:APA91bFqdFXycmdYTXFE-Z58dCBmxVPAUUo87JEmu47wayu_vrT2Ul6pKm_6ftBV7L3O608aTOpeIdETdHGnyFwzd0ihlDGOfTnVcUFiVPVzWGgH4Z-_8Yc"
        ]);

        $user2 =User::findOrFail(1);

        $user2->device_tokens()->create([
            'token'=>"eXLRx-dyro5g3uTuezzcJ9:APA91bFz3KINcd6Azj5w21t_50xPHtvQyN_GRdVGntEkdWE7uHhKBMUnWXuVjm-iR3yGpNm1LW6slZK1vylQr-9OEQuLcwXxl0lWHC-H0tJATXiERpqZNnQ"
        ]);

        $user2->device_tokens()->create([
            'token'=>"fH2HYRrjfF76bapV58-9Ai:APA91bGqUWa_HdhMGBiKL2Fy6PnGP1CFf1QjwrMS3fI1eHk1AzohHdoPwP7sJUjqNEjtyIZ5J-T8fF2m5uHTHLRYlcWOc-b5T-NvwRCkMZhbhsNONUZLbys"
        ]);
    }
}
