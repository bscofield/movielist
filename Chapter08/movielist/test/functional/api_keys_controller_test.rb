require File.dirname(__FILE__) + '/../test_helper'

class ApiKeysControllerTest < ActionController::TestCase
  def test_should_get_index
    login_as :quentin
    get :index
    assert_response :success
    assert_not_nil assigns(:api_keys)
  end

  def test_should_get_new
    login_as :quentin
    get :new, :user_id => users(:quentin).id
    assert_response :success
  end

  def test_should_create_api_key
    login_as :quentin
    assert_difference('ApiKey.count') do
      post :create, :api_key => { :identifier => 'ababa' }, :user_id => users(:quentin).id
    end

    assert_redirected_to user_api_keys_path
  end

  def test_should_show_api_key
    login_as :quentin
    get :show, :id => api_keys(:one).id, :user_id => users(:quentin).id
    assert_response :success
  end

  def test_should_destroy_api_key
    login_as :quentin
    assert_difference('ApiKey.count', -1) do
      delete :destroy, :id => api_keys(:one).id, :user_id => users(:quentin).id
    end

    assert_redirected_to user_api_keys_path
  end
end
