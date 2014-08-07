<?php
/**
 * Deletes a comment
 */

require_once __DIR__ . '/../src/bootstrap.php';
/**
 * @var Comment $comment The comment to delete
 */
$comment = $entityManager->find('Blog\Entity\Comment', $_GET['id']);

if (!$comment) {
    throw new \Exception('Comment not found');
}

$entityManager->remove($comment);
$entityManager->flush();

header(sprintf('Location: view-post.php?id=%id', $comment->getPost()->getId()));
exit;