<?php

class ControllerPost
{
    public function BlogEditAction(): void
    {
        MotorView::show('post/viewBlogEdit');
        // si la requete est, post/blogEdit sans rien derriere on a a faire a la création d'un nouveau post

        // si la requete contient un identifiant on verifie qu'il est valide et cohérent,
            // on passe a la modification
            // on va donc update model blogPage, categoryPage, Category
        // sinon ca veut dire que c'est pas cohérent donc report
    }

}