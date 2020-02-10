<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

/**
 * CheminVideos
 *
 * @ORM\Table(name="chemin_videos")
 * @ORM\Entity
 */
class CheminVideos
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_video", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idVideo;

    /**
     * @var int
     *
     * @ORM\Column(name="categorie_produit", type="integer", nullable=false)
     */
    private $categorieProduit;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_video", type="string", length=255, nullable=false, options={"comment"="Nom de la vidéo sans l'extension"})
     */
    private $nomVideo;

    /**
     * @var string
     *
     * @ORM\Column(name="extension_fichier", type="string", length=8, nullable=false, options={"comment"="Extension du fichier vidéo"})
     */
    private $extensionFichier;

    /**
     * @var bool
     *
     * @ORM\Column(name="valide_pour_tous", type="boolean", nullable=false, options={"default"="1","comment"="La vidéo est-elle validée par le siège ?"})
     */
    private $validePourTous = '1';

    /**
     * @var bool
     *
     * @ORM\Column(name="a_supprimer", type="boolean", nullable=false)
     */
    private $aSupprimer = '0';

    /**
     * @var bool
     *
     * @ORM\Column(name="is_halal", type="boolean", nullable=false)
     */
    private $isHalal = '0';

    /**
     * @var int
     *
     * @ORM\Column(name="is_petit_dejeuner", type="integer", nullable=false)
     */
    private $isPetitDejeuner = '0';


}
