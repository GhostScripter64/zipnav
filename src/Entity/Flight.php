<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use MyCLabs\Enum\Enum;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FlightRepository")
 * @ORM\Table(name="flight",
 *      uniqueConstraints={@ORM\UniqueConstraint(name="flight",columns={"ap_from","ap_to"})}
 * )
 * @UniqueEntity(
 *     fields={"from", "to"},
 *     errorPath="from",
 *     message="A flight to this destination already exists."
 * )
 */

class Flight
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Airport", inversedBy="outbound")
     * @ORM\JoinColumn(name="ap_from", referencedColumnName="id")
     */
    private $from;

    /**
     * @ORM\ManyToOne(targetEntity="Airport", inversedBy="inbound")
     * @ORM\JoinColumn(name="ap_to", referencedColumnName="id")
     */
    private $to;

    /**
     * @ORM\Column(type="text")
     */
    private $kfm;

    //Getters & Setters
    public function getId(): ?int
    {
      return $this->id;
    }

    public function getFrom()
    {
      return $this->from;
    }

    public function setFrom($input)
    {
      $this->from = $input;
    }

    public function getTo()
    {
      return $this->to;
    }

    public function setTo($input)
    {
      $this->to = $input;
      //$this->to = $this->em->getRepository(Airport::class)->find($input);
    }

    public function getKfm(): ?string
    {
        //Get JSON route from table
        $lsl = "";
        if (isset($this->kfm)) {
            //Convert JSON route to CSV
            $json = $this->kfm;
            //$json = str_replace("-0", "0", $json);
            $json = str_replace("<", "'<", $json);
            $json = str_replace(">", ">'", $json);
            $json = json_decode($json);
            $csv = str_getcsv($json->route, ",", "'");

            //Convert CSV to LSL
            for ($i = 0; $i < count($csv); $i++) {

                if ($i % 3 == 0) {
                    //$lsl .= "&#09;" . $csv[$i] . ",";
                    $lsl .= "\t" . $csv[$i] . ",";

                } elseif ($i % 3 == 1) {
                    $lsl .= "llEuler2Rot(" . $csv[$i] . " * DEG_TO_RAD),";

                } else {
                    $lsl .= $csv[$i] . "\n";
                }
            }

            $lsl = str_replace(",", ", ", $lsl);
            $lsl = str_replace("\n", ",\n", $lsl);
            $lsl = substr($lsl, 0, -strlen(",\n")) . "\n";

            //Add LSL pre and postfix code
            $lsl = "llSetKeyframedMotion([\n" . $lsl;
            $lsl .= "], [";

            //Reverse Engineer KFM Flags
            $csv = str_getcsv($json->flags, ",", "'");
            $kfm = KFM_OPTIONS::search((int)$csv[0]);
            $lsl .= $kfm . ", ";
            $kfm = __NAMESPACE__ . "\\" . $kfm;
            $lsl .= $kfm::search((int)$csv[1]);
            $lsl .= "]);";
        }
        return $lsl;
    }

    public function setKfm(string $route): self
    {
        //Convert LSL to CSV algorithm
        $lsl = $route;

        $lsl = str_replace(" ", "", $lsl);
        $lsl = str_replace("\t", "", $lsl);
        $lsl = str_replace("llSetKeyframedMotion([", "", $lsl);
        $lsl = str_replace("llEuler2Rot(", "", $lsl);
        $lsl = str_replace("]);", "", $lsl);
        $lsl = str_replace("*DEG_TO_RAD)", "", $lsl);
        $lsl = preg_replace("[\/\/\w+]", '', $lsl);
        $lsl = str_replace("\r", "", $lsl);
        $lsl = str_replace("\n", "", $lsl);
        $lsl = str_replace("-0", "0", $lsl);

        //Split CSV in route and flags
        $csv = explode("],[", $lsl);
        $route = $csv[0];
        if (count($csv) < 2) {
          $flags = "0,0";
        } else {
          $flags = explode(",", $csv[1]);

          for ($i = 0; $i < count($flags); $i = $i + 2) {
              $kfm = $flags[0];

              //Convert to integer
              $enum = __NAMESPACE__ . "\\KFM_OPTIONS::" . $flags[$i];
              $flags[$i] = $enum();

              //Convert to integer
              $enum = "" . __NAMESPACE__ . "\\" . $kfm . "::" . $flags[$i + 1];
              $flags[$i + 1] = $enum();
          }
          $flags = implode(",", $flags);
        }

        $route = json_encode(array("route" => $route, "flags" => $flags));

        $this->kfm = $route;

        return $this;
    }

    public function getJson(): ?string
    {
      return $this->kfm;
    }
}

class KFM_OPTIONS extends Enum
{
    const KFM_COMMAND = 0;
    const KFM_MODE = 1;
    const KFM_DATA = 2;
}

class KFM_COMMAND extends Enum
{
    const KFM_CMD_PLAY = 0;
    const KFM_CMD_STOP = 1;
    const KFM_CMD_PAUSE = 2;
}

class KFM_MODE extends Enum
{
    const KFM_FORWARD = 0;
    const KFM_LOOP = 1;
    const KFM_PING_PONG = 2;
    const KFM_REVERSE = 3;
}

class KFM_DATA extends Enum
{
    const KFM_ROTATION = 1;
    const KFM_TRANSLATION = 2;
}
