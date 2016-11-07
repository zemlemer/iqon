<?php

namespace App\Models;

use App\Structures\DbCondition;
use App\Contracts\QueryBuilderInterface;
use App\Structures\DBExpression;

/**
 * Class Comment
 * @package App\Models
 *
 * @property int $pub_date
 * @property int $user_id
 * @property string $comment_text
 */

class Comment extends BaseModel
{
    const
        PRIMARY_KEY = 'id',
        TABLE = 'comments',

        LEFT_KEY = 'lft',
        RIGHT_KEY = 'rgt',
        LEVEL = 'lvl';


    protected $attributes = [
        self::RIGHT_KEY, self::LEFT_KEY, self::LEVEL,
        'id', 'user_id', 'pub_date', 'comment_text'
    ];

    public function getPrimaryKey() : string
    {
        return static::PRIMARY_KEY;
    }

    public function getRoot() : QueryBuilderInterface
    {
        return $this->getQueryBuilder()
            ->select()
            ->where(new DbCondition(static::LEFT_KEY, '=', '1'));

    }

    public function getDescendants($depth = null) : QueryBuilderInterface
    {
        $query = $this->getQueryBuilder()
            ->select()
            ->where(new DbCondition(static::LEFT_KEY, '>', $this->{static::LEFT_KEY}))
            ->where(new DbCondition(static::RIGHT_KEY, '<', $this->{static::RIGHT_KEY}));
        if($depth) {
            $query->where(new DbCondition(static::LEVEL, '=', $this->{static::LEVEL} + 1));
        }

        return $query;
    }

    public function append(self $comment) : self
    {
        $comment->pub_date = time();

//        UPDATE my_tree SET left_key = left_key + 2, right_ key = right_ key + 2 WHERE left_key > $right_ key
        $this->getQueryBuilder()->update([
            static::LEFT_KEY => new DBExpression(static::LEFT_KEY . " + 2"),
            static::RIGHT_KEY => new DBExpression(static::RIGHT_KEY . " + 2"),
        ])  ->where(new DBExpression(static::LEFT_KEY . " > " . $this->{static::RIGHT_KEY}))
            ->execute();

//    UPDATE my_tree SET right_key = right_key + 2 WHERE right_key >= $right_key AND left_key < $right_key
        $this->getQueryBuilder()->update([
            static::RIGHT_KEY => new DBExpression(static::RIGHT_KEY . " + 2"),
        ])  ->where(new DBExpression(static::RIGHT_KEY . " >= " . $this->{static::RIGHT_KEY}))
            ->where(new DBExpression(static::LEFT_KEY . " < " . $this->{static::RIGHT_KEY}))
            ->execute();

//    INSERT INTO my_tree SET left_key = $right_key, right_key = $right_key + 1, level = $level + 1 [дополнительные параметры ]
        $comment->{static::LEFT_KEY} = $this->{static::RIGHT_KEY};
        $comment->{static::RIGHT_KEY} = $this->{static::RIGHT_KEY} + 1;
        $comment->{static::LEVEL} = $this->{static::LEVEL} + 1;

        return $comment->save();
    }

    public function delete()
    {
        $this->getQueryBuilder()
            ->delete()
            ->where(new DBExpression(static::LEFT_KEY . " >= " . $this->{static::LEFT_KEY}))
            ->where(new DBExpression(static::RIGHT_KEY . "<= " . $this->{static::RIGHT_KEY}))
            ->execute();

        $changeVal = $this->{static::RIGHT_KEY} - $this->{static::LEFT_KEY} + 1;

        $this->getQueryBuilder()->update([
            static::LEFT_KEY => new DBExpression("IF(" . static::LEFT_KEY . " > " . $this->{static::LEFT_KEY} . ", "
                . static::LEFT_KEY . " - " . $changeVal . ", " . static::LEFT_KEY . ")"),
            static::RIGHT_KEY => new DBExpression(static::RIGHT_KEY . " - " . $changeVal),
        ])  ->where(new DBExpression(static::RIGHT_KEY . " > " . $this->{static::RIGHT_KEY}))
            ->execute();

        return true;
    }

    public function hasChildren()
    {
        return ($this->{self::RIGHT_KEY} - $this->{self::LEFT_KEY} > 1);
    }

    public function getTbName() : string
    {
        return static::TABLE;
    }
}
