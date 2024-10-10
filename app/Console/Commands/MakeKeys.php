<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use App\Models\AttributeFirm;
use App\Models\Group;
use App\Models\GroupFirm;
use App\Models\Keyword;
use App\Models\KeywordFirm;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakeKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:keys';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add keys to firms';

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
        //$this->create();
        //$this->make();
        $this->clear();
    }

    public function clear()
    {
        $keys = KeywordFirm::groupBy(['keyword_id', 'city_id'])
            ->select(DB::raw('count(*) as count, keyword_id, city_id'))
            ->get();
        foreach ($keys as $key) {
            if ($key->count < 10) {
                KeywordFirm::where(['city_id' => $key->city_id, 'keyword_id' => $key->keyword_id])
                    ->delete();
                $this->info($key->keyword_id);
            }
        }
    }

    private function make()
    {
        KeywordFirm::truncate();
        $attributes = Attribute::where('tag', 'like', 'car\_%')->get();
        foreach ($attributes as $attribute) {
            $keywords = Keyword::where('name', 'like', "%{$attribute->name}%")->get();
            foreach ($keywords AS $keyword) {
                $this->info("{$attribute->name} => {$keyword->name}");
                $firms = AttributeFirm::where('attribute_id', $attribute->id)->get();
                $firms = $firms->shuffle()->take(60);
                $ins = [];
                foreach ($firms AS $firm) {
                    $ins[] = [
                        'firm_id'    => $firm->firm_id,
                        'keyword_id' => $keyword->id,
                        'city_id'    => $firm->city_id
                    ];
                }
                if ($ins) {
                    try {
                        KeywordFirm::insert($ins);
                    } catch (\PDOException $exception) {

                    }
                }
            }
        }
    }

    private function create()
    {
        GroupFirm::with(['gisGroup.group', 'firm'])->chunk(1000, function ($firms) {
            foreach ($firms as $firm) {
                $group = data_get($firm, 'gisGroup.group');
                $keys = $this->getChildrens($group);
                $firm->firm->keywords()->sync($keys);
            }
            $this->info($firm->id);
        });
    }

    private function getChildrens($group)
    {
        $childrens = collect();
        if (!$group) return $childrens;
        $childrens->push($group);
        if (!$group->childrens) return $childrens;
        $key = $group->childrens->shuffle()->first();
        if (!$key) return $childrens;
        foreach ($key->childrens AS $children) {
            $childrens->push($children);
            $key = $children->childrens->shuffle()->first();
            if (!$key) continue;
            foreach ($key->childrens as $children2) {
                $childrens->push($children2);
                $key = $children2->childrens->shuffle()->first();
                if (!$key) continue;
                foreach ($key->childrens as $children3) {
                    $childrens->push($children3);
                    $key = $children3->childrens->shuffle()->first();
                    if (!$key) continue;
                    foreach ($key->childrens as $children4) {
                        $childrens->push($children4);
                        $key = $children4->childrens->shuffle()->first();
                        if (!$key) continue;
                        foreach ($key->childrens as $children5) {
                            $childrens->push($children5);
                            $key = $children5->childrens->shuffle()->first();
                            if (!$key) continue;
                            foreach ($key->childrens as $children6) {
                                $childrens->push($children6);
                            }
                        }
                    }
                }
            }
        }
        $result = collect();
        foreach ($childrens->shuffle() AS $children) {
            $result->push($children->id);
        }
        return $result->slice(0, mt_rand(5, 15))->shuffle();
    }
}
