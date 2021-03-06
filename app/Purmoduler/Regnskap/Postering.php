<?php namespace Pur\Purmoduler\Regnskap;

use Illuminate\Database\Eloquent\Model;
use NumberFormatter;

class Postering extends Model {

    protected $table = 'posteringer';

    protected $fillable = ['belop', 'ant_lagringer', 'er_fasit', 'bilag_id', 'kontokode'];

    const CREATED_AT = 'tid_opprettet';
    const UPDATED_AT = 'tid_endret';

    /**
     * Formattert tidspunkt for da posteringen ble opprettet
     *
     * @return mixed
     */
    public function tidOpprettet()
    {
        return $this->tid_opprettet->format('d.m.y H:i');
    }

    /**
     * Formattert tidspunkt for da posteringen sist ble endret
     *
     * @return mixed
     */
    public function tidEndret()
    {
        return $this->tid_endret->format('d.m.y H:i');
    }

    public function setBelopAttribute($belop)
    {
        $belop = str_replace(' ', '', $belop);
        $belop = str_replace(',', '.', $belop);
        $belop = str_replace('–', '-', $belop);
        $belop = (float) $belop;
        $belop = round($belop, 2);

        $this->attributes['belop'] = $belop;
    }

    /**
     * Posteringens beløp formattert som kronebeløp
     *
     * @return float
     */
    public function belop()
    {
        return  ($this->belop != 0) ? number_format($this->belop, 2, ',', ' ') : '';
    }

    /**
     * Sant hvis det finnes en fasitpostering som er lik denne posteringen
     *
     * @return bool
     */
    public function erKorrekt()
    {
        return $this->fasitpostering() != null;
    }

    /**
     * Begrenser spørreresultatet til rader med oppgitt kontokode
     *
     * @param $query
     * @param $kontokode
     * @return mixed
     */
    public function scopeMedKontokode($query, $kontokode)
    {
        return $query->whereKontokode($kontokode);
    }

    /**
     * Begrenser spørreresultatet til rader med oppgitt beløp +/- 1 kr
     *
     * @param $query
     * @param $belop
     * @return mixed
     */
    public function scopeMedBelop($query, $belop)
    {
        return $query->where('belop', '<=', $belop + 1.0)->where('belop', '>=', $belop - 1.0);
    }

    /**
     * Begrenser spørreresultatet til rader der kolonnen 'er_fasit' = 'false'
     *
     * @param $query
     */
    public function scopeAvElev($query)
    {
        $query->whereErFasit(false);
    }

    /**
     * Begrenser spørreresultatet til rader der kolonnen 'er_fasit' = 'true'
     *
     * @param $query
     */
    public function scopeFasit($query)
    {
        $query->whereErFasit(true);
    }

    /**
     * Bilaget som posteringen tilhører
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bilag()
    {
        return $this->belongsTo('Pur\Purmoduler\Regnskap\Bilag');
    }

    /**
     * Kontoen som brukes i posteringen
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function konto()
    {
        return $this->belongsTo('Pur\Purmoduler\Regnskap\Konto', 'kontokode', 'kontokode');
    }

    /**
     * Returnerer bilagets første fasitpostering som likner tilstrekkelig på posteringen
     * eller returnerer posteringen selv, hvis posteringen selv er en fasitpostering
     *
     * @return \Pur\Purmoduler\Regnskap\Postering
     */
    public function fasitpostering()
    {
        if ($this->er_fasit) return $this;

        return $this->bilag->fasitposteringer()->medKontokode($this->kontokode)->medBelop($this->belop)->first();
    }
}
