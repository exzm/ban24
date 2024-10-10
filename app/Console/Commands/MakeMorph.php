<?php

namespace App\Console\Commands;

use App\Models\City;
use App\Models\Firm;
use App\Models\Group;
use App\Models\Subtitle;
use Illuminate\Console\Command;

class MakeMorph extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:morph';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make morpher';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //$this->subtitle();
        //$this->city();
        $this->groups();
    }

    private function groups()
    {
        $groups = Group::where('level', 1)->get();
        foreach ($groups as $group) {
            $str = urlencode($group->name);
            $cases = file_get_contents("http://kotel:rjnkj,fpf@kotel.spravka.today/api/morpher/declension?string={$str}&key=searchkotel");
            $cases = json_decode($cases, true);
            $ed = $cases['ed'];
            $mn = [];
            if ($cases['mn']['im'] != "#ERROR: Parameter 1 'text' is plural.") {
                $mn = $cases['mn'];
            }
            $cases = ['ed' => $ed, 'mn' => $mn];
            $group->data = $cases;
            $group->update();
            $this->info($group->name);
        }

    }

    private function city()
    {
        $firms = City::chunk(1000, function ($cities) {
            foreach ($cities as $city) {
                $str = urlencode($city->name);
                $cases = file_get_contents("http://kotel:rjnkj,fpf@kotel.spravka.today/api/morpher/declension?string={$str}&key=searchkotel");
                $cases = json_decode($cases, true);
                $ed = $cases['ed'];
                $mn = [];
                if ($cases['mn']['im'] != "#ERROR: Parameter 1 'text' is plural.") {
                    $mn = $cases['mn'];
                }
                $cases = ['ed' => $ed, 'mn' => $mn];
                $city->cases = $cases;
                $city->update();
                $this->info($city->name);
            }
        });
    }

    private function subtitle()
    {
        $firms = Firm::chunk(1000, function ($firms) {
            foreach ($firms as $firm) {
                if ($firm->subtitle) {
                    $subtitle = Subtitle::where('name', $firm->subtitle)->first();
                    if (!$subtitle) {
                        $str = urlencode($firm->subtitle);
                        $cases = file_get_contents("http://kotel:rjnkj,fpf@kotel.spravka.today/api/morpher/declension?string={$str}&key=searchkotel");
                        $cases = json_decode($cases, true);
                        $ed = $cases['ed'];
                        $mn = [];
                        if ($cases['mn']['im'] != "#ERROR: Parameter 1 'text' is plural.") {
                            $mn = $cases['mn'];
                        }
                        $cases = ['ed' => $ed, 'mn' => $mn];
                        Subtitle::create(['name' => $firm->subtitle, 'data' => $cases]);
                    }

                }
            }
        });
    }
}
