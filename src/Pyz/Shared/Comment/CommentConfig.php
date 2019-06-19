<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\Comment;

use Spryker\Shared\Comment\CommentConfig as SprykerCommentConfig;

class CommentConfig extends SprykerCommentConfig
{
    /**
     * @return string[]
     */
    public function getCommentAvailableTags(): array
    {
        return [
            'attached',
            '2017-01 Release',
            'TODO: Johny@email.com',
            'Case-sensitive tag',
        ];
    }
}
