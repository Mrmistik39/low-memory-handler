<?php
//echo 5.2 * 1024 * 1024;
$active = 0;
$_fi = function (): array {
    $data = explode("\n", file_get_contents("/proc/meminfo"));
    $info = [];
    foreach ($data as $line) {
        $_ = explode(":", $line);
        if(count($_) > 1) {
            list($key, $val) = $_;
            $info[$key] = trim($val);
        }
    }
    return $info;
};
while(true){
    $info = $_fi();
    $inactive = (float)(round(((int)$info['Inactive'])/1024/1024, 2).PHP_EOL);
    if($inactive < 0.4){
        if((time() - $active) >= 60) $active = time(); else return false;
        if($active == 0) $active = time();
        exec('gdbus call --session     --dest=org.freedesktop.Notifications     --object-path=/org/freedesktop/Notifications     --method=org.freedesktop.Notifications.Notify     "" 0 "" \'Process controller\' \'Critically low RAM. Критически мало памяти озу, вероятно зависание \'     \'[]\' \'{"urgency": <1>}\' 5000');
    }
    sleep(1);
}
