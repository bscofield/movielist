require File.dirname(__FILE__) + '/../test_helper'

class CommentsControllerTest < ActionController::TestCase
  def test_should_get_index
    stub_for_facebook
    get :index, :title => movies(:one).title
    assert_response :success
    assert_not_nil assigns(:comments)
  end

  def test_should_get_index_as_xml
    stub_for_facebook
    get :index, :movie_id => movies(:one).id, :format => 'xml'
    assert_response :success
    assert_not_nil assigns(:comments)
  end

  def test_should_create_comment
    stub_for_facebook
    assert_difference('Comment.count') do
      post :create, :movie_id => movies(:one).id, :comment => { }
    end

    assert_redirected_to movie_path(movies(:one))
  end
  
  def test_failed_comment_create_should_rerender_movie
    stub_for_facebook
    Comment.any_instance.expects(:save).returns(false)
    post :create, :movie_id => movies(:one).id, :comment => {}
    assert_template 'show'
  end

  def test_should_destroy_comment
    stub_for_facebook
    login_as :quentin
    assert_difference('Comment.count', -1) do
      delete :destroy, :movie_id => movies(:one).id, :id => comments(:one).id
    end

    assert_redirected_to movie_comments_path(movies(:one))
  end
end
