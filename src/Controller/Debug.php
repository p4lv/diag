<?php


namespace Diag\Controller;


use Diag\LogReader;
use Symfony\Component\HttpFoundation\Response;

class Debug
{
    private $container;

    public function __construct()
    {

    }

    public function setContainer(\Psr\Container\ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getIndex()
    {

        $template = '<table>
<tr><td colspan="2">Parameters</td></tr>
<tr><th>key</th><th>value</th></tr>';
        $data = $this->container->getparameterBag()->all();
        foreach ($data as $key => $val) {
            $template .= "<tr><td>{$key}</td><td>{$val}</td></tr>";
        }
        $template .='</tr></table> <hr>';

        /** @var LogReader $logReader */
        $logReader = $this->container->get(\Diag\LogReader::class);
        $lastRecords = $logReader->getLast(3);


$i =0;
        foreach ($lastRecords as $item) {

            if(!is_array($item)) {
                continue;
            }
$template .= $i . ' record<hr>';
            $record = '<table>';
            foreach ($item as $k => $value) {
                $record = "<tr><td>{$k}</td><td>{$value}</td></tr>";
            }
            $record .= '</table>';
            $template .= $record;
        }

        return new Response($template);
    }


}