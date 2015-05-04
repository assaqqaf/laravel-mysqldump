<?php namespace Assaqqaf\Mysqldump\Commands;

use Illuminate\Console\Command;
use Ifsnop\Mysqldump as IMysqldump;

class MysqlDumpCommand extends Command {


    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mysqldump:dump';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a dump.sql in storage path';


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
    public function fire()
    {
        //@TODO: display some export statistic
        //@TODO: email the result
        //@TODO: store the backup on the cloud
        $this->info('Start Dumping Database' . PHP_EOL);

        $driver = config('database.default');

        if( $driver !== 'mysql'){
            $this->error('Sorry! The package support only MySql!');
            return false;
        }

        $host = config('database.connections.mysql.host');
        $user = config('database.connections.mysql.username');
        $pass = config('database.connections.mysql.password');
        $database = config('database.connections.mysql.database');

        $this->info('Database Metadata:' . $database . PHP_EOL);
        $this->info('Database Name: '.$database);
        $this->info('Database Host: '.$host);
        $this->info('Database User: '.$user);

        $file = storage_path(). "/database/".time()."_dump.sql";

        try {
            $dump = new IMysqldump\Mysqldump($database, $user, $pass);
            $dump->start($file);
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
