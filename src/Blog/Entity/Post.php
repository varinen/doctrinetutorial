<?php
namespace Blog\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\GenerateValue;
use Doctrine\ORM\Mapping\Column;

/**
 * Blog Post Entity
 *
 * @Entity
 * @Table(indexes={
 *      @Index(name="publication_date_idx", columns="publicationDate")
 * })
 */

class Post
{
    /**
     * @var int
     *
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    protected $id

    /**
     * @var string
     *
     * @Column(type="string")
     *
     */
    protected $title;

    /**
     * @var string
     *
     * @Column(type="text")
     */

    
}
