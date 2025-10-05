<?php

namespace Tests\Feature;

use App\Services\CocomoService;
use Tests\TestCase;

class CocomoEstimationTest extends TestCase
{
    public function test_estimate_nominal_organico_10kloc(): void
    {
        $drivers = array_fill_keys(
            ['RELY','DATA','CPLX','TIME','STOR','VIRT','TURN','ACAP','AEXP','PCAP','VEXP','LTEX','MODP','TOOL','SCED'],
            'nominal'
        );
        [$eaf,$pm,$tdev,$p,$monthly,$total] = CocomoService::estimate(10, 'organico', 1000, $drivers);

        $this->assertEquals(1.0, $eaf);
        $this->assertTrue($pm > 35 && $pm < 37);
        $this->assertTrue($tdev > 9 && $tdev < 10);
        $this->assertTrue($p > 3 && $p < 5);
        $this->assertTrue($total > 35000 && $total < 37000);
    }
}
