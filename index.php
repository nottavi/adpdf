<?php
require __DIR__.'/vendor/autoload.php';
use Spipu\Html2Pdf\Html2Pdf;

$html2pdf = new Html2Pdf();
$html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first test');
$html2pdf->output();

class Graph {
    public function pieChart(Request $request)
    {
        \JpGraph\JpGraph::load();
        \JpGraph\JpGraph::module('pie');

        $data = $request->query->get('data');
        // Create the graph. These two calls are always required
        $graph = new \PieGraph(300,300); 
        $graph->SetScale("textlin");
        $graph->SetShadow();
        //$graph->img->SetMargin(40,30,20,40);
        // Create the bar plots
        $p1 = new \PiePlot(explode(",", $data));
        $graph->Add($p1);
        
        $p1->ShowBorder();
        $p1->SetColor('black');
        $p1->SetSliceColors(array('blue','red'));
        $graph->Stroke();
    }

    /* Quick workaround to generate pie Chart with JpGraph for pdf */
    public function barChart(Request $request)
    {
        \JpGraph\JpGraph::load();
        \JpGraph\JpGraph::module('bar');

        $data = $request->query->get('data');
        // Create the graph. These two calls are always required
        $graph = new \Graph(350,200,'auto'); 
        $graph->SetScale("textlin");
        $graph->SetShadow();
        $graph->img->SetMargin(40,30,20,40);
        // Create the bar plots
        $b1plot = new \BarPlot(explode(",", $data ) );
        $graph->yaxis->SetTickPositions(array(0,20,40,60,80,100), array());
        $graph->SetBox(false);
        $graph->Add( $b1plot );
        $b1plot->SetFillColor(array("#0A569B",'#6E1695','#F2611F'));
        $b1plot->value->Show();
        // Display the graph
        $graph->Stroke();
    }
}

?>
