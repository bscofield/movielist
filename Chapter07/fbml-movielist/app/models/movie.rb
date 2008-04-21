class Movie < ActiveResource::Base
  self.site = 'http://localhost:3000'

  def releases
    Release.find(:all, :params => {:movie_id => id})
  end
end
