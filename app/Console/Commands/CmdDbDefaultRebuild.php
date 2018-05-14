<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CmdDbDefaultRebuild extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:db:default:rebuild';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快速重建預設資料庫';

    /**
     * Create a new command instance.
     *
     * @return void
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
        // 刪除資料庫
        $cmdDropTable = 'DROP DATABASE IF EXISTS tgbot;';
        DB::statement($cmdDropTable);
        $this->info("[OK] $cmdDropTable");

        // 重建資料庫
        $cmdCreateTable = 'CREATE DATABASE tgbot CHARACTER SET utf8 COLLATE utf8_general_ci;';
        DB::statement($cmdCreateTable);
        $this->info("[OK] $cmdCreateTable");

        // 移動至專案目錄
        $path = base_path();;
        shell_exec("cd $path");
        $this->info("[OK] Change path to : $path");

        // 執行 db:migrate
        $cmdDbSeed = 'php artisan migrate';
        $this->info("### $cmdDbSeed ###");
        $cmdDbSeedOutput = shell_exec($cmdDbSeed);
        $this->info($cmdDbSeedOutput);

        // 執行 db:seed
        $cmdDbSeed = 'php artisan db:seed';
        $this->info("### $cmdDbSeed ###");
        $cmdDbSeedOutput = shell_exec($cmdDbSeed);
        $this->info($cmdDbSeedOutput);
    }
}
