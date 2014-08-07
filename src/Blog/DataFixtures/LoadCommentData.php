<?php
namespace Blog\DataFixtures;

use Blog\Entity\Comment;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Comment fixtures
 */
class LoadCommentData implements FixtureInterface, DependentFixtureInterface
{
    /**
     * Number of comments to add to by post
     */
    const NUMBER_OF_COMMENTS_BY_POST = 5;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $posts = $manager->getRepository('Blog\Entity\Post')->findAll();

        foreach ($posts as $post) {
            for ($i = 1; $i <= self::NUMBER_OF_COMMENTS_BY_POST; $i++) {
                $comment = new Comment();
                $comment
                    ->setBody(<<<EOT
                Lorem ipsim dolor sit amet, consectetur adipiscing elit.
EOT
                    )
                    ->setPublicationDate(new \DateTime(sprintf('-%ddays', self::NUMBER_OF_COMMENTS_BY_POST - $i)))
                    ->setPost($post);

                $manager->persist($comment);
            }
        }
        $manager->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return ['Blog\DataFixtures\LoadPostData'];
    }
}