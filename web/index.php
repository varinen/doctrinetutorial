<?php

/**
 * Lists all posts
 */

require_once __DIR__ . '/../src/bootstrap.php';

/**
 * @var $posts \Blog\Entity\Post[] Retrieve the list of all blog posts
 */
$posts = $entityManager->getRepository('Blog\Entity\Post')
    ->findAll();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>My Blog</title>
    </head>
    <body>
        <h1>My Blog</h1>

        <?php
        foreach ($posts as $post) :?>
            <article>
                <h1>
                    <a href="view-post.php?id=<?=$post->getId()?>"><?=htmlspecialchars($post->getTitle())?></a>
                </h1>
                Date of publication:
                <?=$post->getPublicationDate()->format('Y-m-d H:i:s')?>
                <p>
                    <?=nl2br(htmlspecialchars($post->getBody()))?>
                </p>
                <?php if (count($post->getComments())):?>
                <h2>Comments</h2>
                <?php foreach ($post->getComments() as $comment):?>
                    <article>
                        <?=$comment->getPublicationDate()->format('Y-m-d H:i:s')?>
                        <p><?=htmlspecialchars($comment->getBody())?></p>
                        <a href="delete-comment.php?id=<?=$comment->getId()?>">Delete this comment</a>
                    </article>
                    <?php endforeach;?>
                <?php endif;?>
                <form method="POST">
                    <h2>Post a comment</h2>
                    <label>
                        Comment
                        <textarea name="body"></textarea>

                    </label><br>
                    <input type="submit">
                </form>
                <ul>
                    <li>
                        <a href="edit-post.php?id=<?=$post->getId()?>">Edit this post</a>
                    </li>
                    <li>
                        <a href="delete-post.php?id=<?=$post->getId()?>">Delete this post</a>
                    </li>
                </ul>
            </article>
        <?php endforeach ?>
    <?php if (empty($posts)): ?>
    <p>No posts, for now!</p>
    <?php endif;?>
    <a href="edit-post.php">Create a new post</a>

    </body>
</html>