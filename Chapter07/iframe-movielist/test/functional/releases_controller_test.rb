require File.dirname(__FILE__) + '/../test_helper'

class ReleasesControllerTest < ActionController::TestCase
  def test_should_get_index
    stub_for_facebook
    get :index
    assert_response :success
    assert_not_nil assigns(:releases)
  end

  def test_should_get_new
    stub_for_facebook
    login_as :quentin
    get :new
    assert_response :success
  end

  def test_should_create_release
    stub_for_facebook
    login_as :quentin
    assert_difference('Release.count') do
      post :create, :release => { :movie_id => movies(:one).id, :format => 'dvd', :released_on => Date.today }
    end

    assert_redirected_to releases_path
  end
  
  def test_failed_create_should_rerender_form
    stub_for_facebook
    login_as :quentin
    post :create, :release => {}
    assert_template 'new'
  end

  def test_should_get_edit
    stub_for_facebook
    login_as :quentin
    get :edit, :id => releases(:one).id
    assert_response :success
  end

  def test_should_update_release
    stub_for_facebook
    login_as :quentin
    put :update, :id => releases(:one).id, :release => { :format => 'theater'}
    assert_redirected_to releases_path
  end
  
  def test_failed_update_should_rerender_form
    stub_for_facebook
    login_as :quentin
    put :update, :id => releases(:one).id, :release => { :format => nil}
    assert_template 'edit'
  end

  def test_should_destroy_release
    stub_for_facebook
    login_as :quentin
    assert_difference('Release.count', -1) do
      delete :destroy, :id => releases(:one).id
    end

    assert_redirected_to releases_path
  end
  
  def test_xml_should_return_upcoming_releases
    stub_for_facebook
    release = releases(:one)
    release.update_attribute :released_on, 1.week.ago
    get :index, :format => 'xml'
    assert !@response.body.include?(release.movie.title)
  end
end
