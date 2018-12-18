<?php

class Newsletter_News_IndexController extends  Mage_Core_Controller_Front_Action
{

    public function preDispatch()
    {
        parent::preDispatch();

        if (!Mage::helper('news')->isEnabled()) {
            $this->setFlag('', 'no-dispatch', true);
            $this->_redirect('noRoute');
        }
    }
    public function indexAction()
    {
        //echo 'Hello';
        $this->loadLayout();
        $this->renderLayout();
    }

    public function sayHelloAction()
    {
        //echo 'test method';
        $this->loadLayout ();
        $this->renderLayout ();
    }

    public function helloAction()
    {
        //echo 'test method';
        $this->loadLayout ();
        $this->renderLayout ();
        // $data = $this->getRequest()->getPost();
        if ($data = $this->getRequest()->getPost())
        {
            $blogpost = Mage::getModel('news/newspost');
            $blogpost->setTitle($data['title']);
           // $blogpost->setPost($data['post']);
            $blogpost->setCreatedAt(now());
            $blogpost->save();
            $this->_redirect('news/index/sayhello');
        }

    }

    public function createNewPostAction() {
        $blogpost = Mage::getModel('news/newspost');
        $blogpost->setTitle('Code Post!');
        $blogpost->setPost('This post was created from code!');
        $blogpost->setCreatedAt(now());
        $blogpost->save();
        echo 'Post with ID ' . $blogpost->getId() . ' created.';
        $this->showAllBlogPostsAction();
    }

    public function readPostAction() {
        $params = $this->getRequest()->getParams();
        $blogpost = Mage::getModel('news/newspost');
        echo("Loading the blogpost with an ID of ".$params['id']);
        $blogpost->load($params['id']);
        $data = $blogpost->getData();
        var_dump($data);
        $this->showAllBlogPostsAction();
    }

    public function updatePostAction() {
        $blogposts = Mage::getModel('news/newspost')->getCollection();
        foreach($blogposts as $post)
        {
            if($post->getId()%2==0)
            {
                $post->setPost("This is updated by updatePostAction!!!!");
                $post->setUpdatedAt(now());
                $post->save();
            }
        }
        echo 'Posts with even number id has been updated.';
        $this->showAllBlogPostsAction();
    }

    public function deletePostAction() {
        $params = $this->getRequest()->getParams();
        $blogpost = Mage::getModel('news/newspost');
        $blogpost->load($params['id']);
        echo("Deleting the blogpost with an ID of ".$params['id']."<br/>");
        $blogpost->delete();
        echo("The blogpost with an ID of ".$params['id']." has been deleted"."<br/>");

        $this->showAllBlogPostsAction();
    }

    public function showAllBlogPostsAction() {
        $posts = Mage::getModel('news/newspost')->getCollection();
        echo "<table border='1'><tr><th>Post ID</th><th>Post Title</th><th>Content</th><th>Updated At</th><th>Created At</th><th>Last Updated At</th></tr>";
        foreach($posts as $blogpost){
            echo "<tr><td>".$blogpost->getId()."</td>";
            echo "<td>".$blogpost->getTitle()."</td>";
            echo "<td>".$blogpost->getPost()."</td>";
            echo "<td>".$blogpost->getUpdatedAt()."</td>";
            echo "<td>".$blogpost->getCreatedAt()."</td>";
            echo "<td>".$blogpost->getLastUpdatedAt()."</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
}

?>