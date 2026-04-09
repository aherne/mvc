<?php

namespace Test\Lucinda\MVC\Support;

final class TestHelper
{
    /**
     * @return array{status:int,output:string}
     */
    public static function runPhp(string $code): array
    {
        $file = tempnam(sys_get_temp_dir(), "mvc_test_");
        file_put_contents(
            $file,
            "<?php\nrequire ".var_export(dirname(__DIR__, 2)."/vendor/autoload.php", true).";\n".$code
        );

        exec(PHP_BINARY." ".escapeshellarg($file)." 2>&1", $output, $status);
        unlink($file);

        return [
            "status" => $status,
            "output" => implode("\n", $output)
        ];
    }

    public static function createTempFile(string $contents, string $extension = "txt"): string
    {
        $file = tempnam(sys_get_temp_dir(), "mvc_file_");
        if ($extension !== "tmp") {
            $target = $file.".".$extension;
            rename($file, $target);
            $file = $target;
        }
        file_put_contents($file, $contents);
        return $file;
    }
}
