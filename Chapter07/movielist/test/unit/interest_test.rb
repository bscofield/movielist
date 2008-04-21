require File.dirname(__FILE__) + '/../test_helper'

class InterestTest < ActiveSupport::TestCase
  def test_movie_title_should_return_movie_title
    interest = interests(:one)
    assert_equal interest.movie_title, interest.movie.title
  end
  
  def test_assigning_movie_title_should_set_movie
    movie = movies(:one)
    interest = Interest.new :user => users(:quentin), :movie_title => movie.title
    assert_equal interest.movie.id,  movie.id
  end
end
