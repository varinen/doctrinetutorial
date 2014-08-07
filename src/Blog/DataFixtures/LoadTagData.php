<?php
namespace Blog\DataFixtures;

use Blog\Entity\Tag;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Tag fixtures
 */
class LoadTagData implements FixtureInterface, DependentFixtureInterface
{
    /**
     * Number of tags to add by post
     */
    const NUMBER_OF_TAGS = 5;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $tags = [];
        for ($i = 1; $i <= self::NUMBER_OF_TAGS; $i++) {
            $tag = new Tag();
            $tag->setName(sprintf('tag%d', $i));
            $tags[] = $tag;
        }

        $posts = $manager->getRepository('Blog\Entity\Post')->findAll();

        $tagsToAdd = 1;

        foreach ($posts as $post) {
            for ($i = 0; $i <  $tagsToAdd; $i++) {
                $post->addTag($tags[$i]);
            }
            $tagsToAdd = $tagsToAdd % 5 + 1;

        }

        $manager->flush();

        /**
         * {@inheritDoc}
         */
    }

    public function getDependencies()
    {
        return ['Blog\DataFixtures\LoadPostData'];
    }

}