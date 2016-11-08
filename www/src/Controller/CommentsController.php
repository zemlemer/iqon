<?php

namespace App\Controller;

use App\Contracts\ResponseInterface;
use App\Models\Comment;
use App\Controller;

/**
 * Class CommentsController
 *
 * @package App\Controller
 */
class CommentsController extends Controller
{
    /**
     * @return \App\Contracts\ResponseInterface
     */
    public function getList() : ResponseInterface
    {
        $parentID = $this->request->get('id');

        /** @var Comment $root */
        if (is_null($parentID)) {
            $root = $this->getModel()->getRoot()->firstOrFail();
            $depth = 1;
            $tpl = 'index';
            $withChildren = false;
        } else {
            $root = $this->getModel()->findByID($parentID);
            $depth = null;
            $tpl = 'comments';
            $withChildren = true;
        }

        $comments = $root->getDescendants($depth)->orderBy(Comment::LEFT_KEY)->all();

        return $this->buildResponse($tpl, compact('comments', 'withChildren'));
    }

    /**
     * @return \App\Contracts\ResponseInterface
     */
    public function create()
    {
        $parentID = $this->checkPost('parentID');
        $text     = $this->checkPost('commentText');
        $userID   = $this->checkPost('userID');

        $comment = new Comment([
            'comment_text' => $text,
            'user_id' => $userID,
        ]);

        /** @var Comment $parent */
        $parent = (new Comment)->findByID($parentID);

        $comment = $parent->append($comment);

        return $this->buildResponse('comment', compact('comment'));
    }

    /**
     * @return \App\Contracts\ResponseInterface
     */
    public function update()
    {
        $commentID = $this->checkPost('commentID');
        $text     = $this->checkPost('commentText');
        $userID   = $this->checkPost('userID');

        /** @var Comment $comment */
        $comment = (new Comment)->findByID($commentID);

        $comment->comment_text = $text;
        $comment->user_id = $userID;

        $comment->save();

        return $this->getResponse()->setBody($comment->toJSON());
    }

    /**
     * @return \App\Contracts\ResponseInterface
     */
    public function delete()
    {
        $commentID = $this->checkPost('commentID');

        /** @var Comment $comment */
        $comment = (new Comment)->findByID($commentID);

        $comment->delete();

        return $this->getResponse()->setBody('');
    }

    /**
     * @return \App\Models\Comment
     */
    protected function getModel() : Comment
    {
        return new Comment();
    }
}
