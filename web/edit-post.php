<?php
/**
 * Creates or edits posts
 */

use Blog\Entity\Post;
use Blog\Entity\Tag;

require_once __DIR__ . '/../src/bootstrap.php';

//retrieve the blog post if an ID parameter exists
if (isset($_GET['id'])) {
    /**
     * @var $Post $post The post to edit
     */
    $post = $entityManager->find('Blog\Entity\Post', $_GET['id']);

    if (!$post) {
        throw new \Exception('Post not found');
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($post)) {
        $post = new Post();

        $entityManager->persist($post);

        $post->setPublicationDate(new \DateTime());
    }

    $post
        ->setTitle($_POST['title'])
        ->setBody($_POST['body']);

    $newTags = [];
    foreach (explode(',', $_POST['tags']) as $tagName) {
        $trimmedTagName = trim($tagName);
        $tag = $entityManager->find('Blog\Entity\Tag', $trimmedTagName);
        if (!$tag) {
            $tag = new Tag();
            $tag->setName($trimmedTagName);
        }

        $newTags[] = $tag;
    }

    //removes unused tags
    foreach (array_diff($post->getTags()->toArray(), $newTags) as $tag) {
        $post->removeTag($tag);
    }
    //add new tags
    foreach (array_diff($newTags, $post->getTags()->toArray()) as $tags) {
        $post->addTag($tag);
    }

    $entityManager->flush();

    header('Location: index.php');
    exit;
}

/**
 * @var string Page title
 */
$pageTitle = isset($post) ? sprintf('Edit post #%d', $post->getId()) : 'Create a new post';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title><?=$pageTitle?> - My Blog</title>
    </head>
    <body>
    <h1><?=$pageTitle?></h1>
    <form method="POST">
        <label>
            Title
            <input type="text" name="title" value="<?=isset($post) ? $post->getTitle() : ''?>" maxlength="255" required />
        </label><br>
        <label>
            Body
            <textarea name="body" cols="20" rows="10" required><?=isset($post) ? $post->getBody() : ''?></textarea>
        </label><br>
        <label>Tags
            <input name="tags" value="<?=isset($post) ? htmlspecialchars(implode(', ', $post->getTags()->toArray())) : ''?>" required>
        </label><br>
        <input type="submit">
    </form>
    <a href="index.php">Back to the index</a>
    </body>
</html>