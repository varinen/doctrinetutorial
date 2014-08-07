<?php
/**
 * Deletes a blog post
 */

require_once __DIR__ .'/../src/bootstrap.php';

/**
 * @var Post the post to be deleted
 */
$post = $entityManager->find('Blog\Entity\Post', $_GET['id']);

if (!$post) {
    throw new \Exception('Post not found');
}

//Delete the entity and flush
$entityManager->remove($post);
$entityManager->flush();

header("Location: index.php");
exit;