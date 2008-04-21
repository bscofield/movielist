class ReleasesController < ApplicationController
  def index
    @releases = Release.find(:all)

    own_interests = Interest.find_all_by_facebook_id(
      fbsession.session_user_id
    ).map(&:movie_id)
    
    friend_interests = Interest.find(:all, 
      :conditions => ["facebook_id IN (?)", fbsession.friends_get.uid_list]
    ).map(&:movie_id)

    @own_releases = @releases.select { |release| 
      own_interests.include?(release.movie_id) 
    }

    all_friend_releases = @releases.select { |release| 
      friend_interests.include?(release.movie_id) 
    }
    @friend_releases = all_friend_releases.reject { |r| 
      @own_releases.include?(r)
    }

    @other_releases = @releases.dup.reject! { |r| 
      @own_releases.include?(r) || @friend_releases.include?(r) 
    }
  end
end
