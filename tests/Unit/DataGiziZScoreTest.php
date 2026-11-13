<?php

namespace Tests\Unit;

use App\Models\DataGizi;
use PHPUnit\Framework\TestCase;

class DataGiziZScoreTest extends TestCase
{
    public function test_all_zscore_mutators_accept_values_inside_the_plausible_range(): void
    {
        $model = new DataGizi();

        $model->zs_bb_u = '1.25';
        $model->zs_tb_u = '-2.5';
        $model->zs_bb_tb = '6';

        $attributes = $model->getAttributes();

        $this->assertSame(1.25, $attributes['zs_bb_u']);
        $this->assertSame(-2.5, $attributes['zs_tb_u']);
        $this->assertSame(6.0, $attributes['zs_bb_tb']);
    }

    public function test_all_zscore_mutators_replace_out_of_range_values_with_null(): void
    {
        $model = new DataGizi();

        $model->zs_bb_u = '6.01';
        $model->zs_tb_u = '-6.01';
        $model->zs_bb_tb = '100';

        $attributes = $model->getAttributes();

        $this->assertNull($attributes['zs_bb_u']);
        $this->assertNull($attributes['zs_tb_u']);
        $this->assertNull($attributes['zs_bb_tb']);
    }

    public function test_all_zscore_mutators_replace_non_numeric_and_empty_values_with_null(): void
    {
        $model = new DataGizi();

        $model->zs_bb_u = 'invalid';
        $model->zs_tb_u = '';
        $model->zs_bb_tb = null;

        $attributes = $model->getAttributes();

        $this->assertNull($attributes['zs_bb_u']);
        $this->assertNull($attributes['zs_tb_u']);
        $this->assertNull($attributes['zs_bb_tb']);
    }

    public function test_all_zscore_mutators_accept_the_lower_boundary(): void
    {
        $model = new DataGizi();

        $model->zs_bb_u = '-6';
        $model->zs_tb_u = '-6';
        $model->zs_bb_tb = '-6';

        $attributes = $model->getAttributes();

        $this->assertSame(-6.0, $attributes['zs_bb_u']);
        $this->assertSame(-6.0, $attributes['zs_tb_u']);
        $this->assertSame(-6.0, $attributes['zs_bb_tb']);
    }
}
