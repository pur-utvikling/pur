<?php namespace Pur\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use PDF;
use Pur\Besvarelse;
use Pur\Http\Requests;
use Pur\Oppgavesett;
use Pur\Purmoduler\Regnskap\Konto;
use Pur\Services\BesvarelseTjeneste;

class BesvarelseController extends Controller
{

    private $bruker;

    public function __construct()
    {
        $this->middleware('auth');
        $this->bruker = Auth::user();
    }

    /**
     * List opp besvarelser
     *
     * @return Response
     */
    public function opplist()
    {
        //
    }

    /**
     * Opprett og lagre besvarelse.
     *
     * @return Response
     */
    public function lagre(Oppgavesett $oppgavesett)
    {
        if($oppgavesett->erAapent()) {
            $besvarelseTjeneste = new BesvarelseTjeneste();
            $besvarelse = $besvarelseTjeneste->opprett($this->bruker, $oppgavesett);
            //return redirect()->route('besvarelser.rediger', $besvarelse);
        }

        flash('Ny besvarelse ble opprettet');

        return redirect()->route('oppgavesett.opplist');
    }

    /**
     * Vis besvarelse.
     *
     * @param Besvarelse $besvarelse
     * @return Response
     */
    public function vis(Besvarelse $besvarelse)
    {
        $this->authorize($besvarelse);

        return view('besvarelser.vis', compact('besvarelse'));
    }

    /**
     * Åpne besvarelse for redigering.
     *
     * @param  Besvarelse $besvarelse
     * @return Response
     */
    public function rediger(Besvarelse $besvarelse)
    {
        $this->authorize($besvarelse);

        // TODO : Gjør Purmodul-uavhengig:
        $bilagssamling = BesvarelseTjeneste::besvarelseBilag($besvarelse);
        $selectKontoer = Konto::alleSomKodeNavnTabell();

        return view('besvarelser.rediger', compact('besvarelse', 'bilagssamling', 'selectKontoer'));
    }

    /**
     * Lagre endringer i besvarelse.
     *
     * @param  int $id
     * @return Response
     */
    public function oppdater(Besvarelse $besvarelse)
    {
        // TODO: implementér
        return "<i>Oppdater besvarelse med id " . $besvarelse->id . "</i>";

    }

    /**
     * Generer PDF av besvarelse.
     *
     * @param  int $id
     * @return Response
     */
    public function genererPdf(Besvarelse $besvarelse)
    {
        $pdf = PDF::loadView('besvarelser.pdf', [
            'besvarelse' => $besvarelse,
            'tidLevert' => $besvarelse->tidLevert() != null ?
                $besvarelse->tidLevert() : Carbon::now()->format('d.m.y H:i')
        ]);

        $filnavn = 'PUR-besvarelse_' . $besvarelse->id . '.pdf';

        return $pdf->stream($filnavn);
    }

    /**
     * Slett besvarelse
     *
     * @param  int $id
     * @return Response
     */
    public function slett(Besvarelse $besvarelse)
    {
        $besvarelse->delete();

        flash('Besvarelsen ble slettet');

        // TODO: Gjør purmoduluavhengig:
        return redirect('/regnskap/oppgavesett');
    }
}
