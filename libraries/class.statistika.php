<?php

class Statistika
{
    private $data = [];
    private $n;

    public function __construct($data)
    {
        $this->data = $data;
        $this->n = count($data);
    }

    public function getData(){
        $str = '';
        foreach($this->data as $d)
        {
            $str .= $d . ' ';
        }
        return $str;
    }

    public function getJangkauan()
    {
        return max($this->data) - min($this->data);
    }

    public function getBanyakKelas()
    {
        return floor(1 + (3.3 * log10($this->n)));
    }

    public function getPanjangKelas()
    {
        return ceil($this->getJangkauan() / $this->getBanyakKelas());
    }

    public function getQuartil($i) // 1: Q1, 2: Q2, 3: Q3
    {
        sort($this->data);
        if ($this->n % 2 == 0) // Genap
        {
            if ($i == 1)
            {
                return $this->data[(($this->n + 2)/4) - 1];
            }
            else if ($i == 2)
            {
                return 0.5 * ($this->data[($this->n/2) - 1] + $this->data[(($this->n/2)+1) - 1]);
            }
            else if ($i == 3)
            {
                return $this->data[((3 * $this->n + 2)/4) - 1];
            }
            else
            {
                return 'tidak ada quartil '. $i;
            }
        }
        else //Ganjil
        {
            return $this->data[($i * ($this->n + 1) / 4) - 1];
        }
    }

    public function getMean()
    {
        return array_sum($this->data) / $this->n ;
    }

    public function getMedian()
    {
        return $this->getQuartil(2);
    }

    public function getModus()
    {
        $a = array_count_values($this->data);
        foreach ($a as $key => $value) 
        {
            if ($value == max($a)) 
            {
                return $key;
            }
        }
    }

    public function getStandarDeviasi()
    {
        return sqrt($this->getVarian());
    }

    public function getVarian()
    {
        $n = $this->n;
        $xa = 0;
        foreach ($this->data as $x) 
        {
            $xa += pow($x, 2);
        }
        $xb = pow(array_sum($this->data), 2);
        return ($n * $xa - $xb) / ($n * ($n - 1));
    }

    public function getKurtosis()
    {
        $mean = $this->getMean();
        $sum = 0;
        foreach ($this->data as $x) {
            $sum += pow(($x - $mean), 4);
        }
        return ((1 / $this->n) * $sum) / pow($this->getStandarDeviasi(), 4);
    }

    public function getLeptokurtik()
    {
        if ($this->getKurtosis() > 3)
            return 'ya';
        else return 'tidak';
    }
}

?>