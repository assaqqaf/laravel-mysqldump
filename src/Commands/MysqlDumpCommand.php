<?php namespace Assaqqaf\Mysqldump\Commands;
use Illuminate\Console\Command;
use Ifsnop\Mysqldump as IMysqldump;

class MySqlDumpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'database:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a mysql dump database folder';

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
        //@TODO: display some export statistic
        //@TODO: email the result
        //@TODO: store the backup on the cloud
        $this->info('Start Dumping Database' . PHP_EOL);
        $driver = config('database.default');
        if ($driver !== 'mysql') {
            $this->error('Sorry! The package support only MySql!');
            return false;
        }
        $host = config('database.connections.mysql.host');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');
        $this->info('Database Metadata:' . $database . PHP_EOL);
        $this->info('Database Name: ' . $database);
        $this->info('Database Host: ' . $host);
        $this->info('Database User: ' . $user);
        $file = storage_path() . "/database/" . time() . "_dump.sql";

        if (!\File::exists(storage_path() . "/database")) {
            \File::makeDirectory(storage_path() . '/database');
        }

        try {
            $str = "mysql:host=$host;dbname=$database";
            $dump = new IMysqldump\Mysqldump($str, $user, $pass);
            $dump->start($file);
            $this->info("Dump created successfully");
        } catch (\Exception $e) {
            $this->error('mysqldump-php error: ' . $e->getMessage());
            return false;
        }
        // IF NECESSARY, YOU CAN ADD exec("sudo mysqldump..... if some there is a permission issue
        //$this->info("mysqldump -h " . $host . " -u " . $user . " -p " . $pass . " --no-create-info " . $name . " > " . $file);
        //exec("mysqldump -h " . $host . " -u " . $user . " -p" . $pass . " --no-create-info " . $database . " > " . $file);
        $this->info("Dump file has been created at. " . $file . PHP_EOL);
    }
}
