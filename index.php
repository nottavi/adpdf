<?php
require __DIR__.'/vendor/autoload.php';

setlocale( LC_ALL, 'fr-FR');
use Spipu\Html2Pdf\Html2Pdf;

$faker = Faker\Factory::create();

$content .= <<< EOT
<style type="text/css">
table {
    width: 100%;
}

table.border {
    border-collapse: collapse;
}

td, th {
    padding: 8px;
}

table.border td, table.border th {
    border: 1px solid #eeeeee;
    text-align: left;
}

th {
    background-color: #eeeeee;
}

.odd {
    background-color: #ffffff;
}
.even {
    background-color: #eeeeee;
}

.header {
    background-color: #454c52;
    padding: 5px;
}

</style>
<page orientation='P' format='A4' backtop="36px" backbottom="17mm" backleft="0" backright="0">
<page_header backcolor="#eeeeee">
<table style="border:none;">
<tr class="header">
<td style="width:25%;text-align:left;"><img src="./assets/images/logo.png" border=0 width=108 height=26/></td>
<td style="width:75%;text-align:right;color:#fff;">Vos résultats du 14/12/2017</td>
</tr>
</table>
</page_header>
<h1>Vos résultats</h1>
<table>
<tr>
<td style="width:50%; text-align:center;">
<img src="http://chart.googleapis.com/chart?cht=bvg&chs=350x350&chd=t:40,70,30&chxt=x,y&chxs=0,ff0000,12,0,lt|1,0000ff,10,1,lt&chco=1f5da2|6912a0|ec680c&chdl=Savoir-faire%20techniques|Savoirs|Savoir-faire%20organisationnels" />
</td>
<td style="width:50%; text-align:center;">
<img src="http://chart.googleapis.com/chart?cht=bvg&chs=350x350&chd=t:40,70,30&chxt=x,y&chxs=0,ff0000,12,0,lt|1,0000ff,10,1,lt&chco=1f5da2|6912a0|ec680c&chdl=Savoir-faire%20techniques|Savoirs|Savoir-faire%20organisationnels" />
</td>
</tr>
<tr>
<td class="even" style="text-align:center;"><strong>Chauffeur-livreur</strong></td>
<td class="even" style="text-align:center;"><strong>Ambulancier</strong></td>
</tr>
</table>

<hr/>
<h2>Savoir-faire techniques Chauffeur-livreur</h2>
<p>&nbsp;</p>
<table>
<tr>
<td style="width:60%;background-color:#1f5da2;">60%</td>
<td style="width:40%;background-color:#f00;">&nbsp;</td>
</tr>
</table>
<table class="border">
<tr>
    <th>Compétences</th>
    <th>Oui</th>
    <th>Non</th>
</tr>
EOT;

for( $i=0; $i < 10; $i++ ):
    $class = ($i % 2 == 0) ? "odd" : "even";
    $content .="<tr class='$class'>";
    $content .="<td style='width:70%; text-align:left;'>&nbsp;".$faker->text."</td>";
    $content .="<td style='width:15%; text-align:center;'>&nbsp;</td>";
    $content .="<td style='width:15%; text-align:center;'>&nbsp;</td>";
    $content .="</tr>";

endfor;

$content .=<<<EOT
</table>

<page_footer>
<table style="width: 100%; border: solid 1px black;">
    <tr>
        <td style="text-align: left;    width: 50%">www.rock-and.lol/mde_translog</td>
        <td style="text-align: right;    width: 50%">page [[page_cu]]/[[page_nb]]</td>
    </tr>
</table>
</page_footer>
</page>

EOT;

//echo $content;

//$content = file_get_contents("http://www.rock-and.lol/mde_translog/end");
$html2pdf = new \Spipu\Html2Pdf\Html2Pdf( ); 
//$font = 'dejavusans';
//$html2pdf->setDefaultFont($font);
$html2pdf->writeHTML( $content  );
$html2pdf->output('./test.pdf');

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
