class CommentsController < ApplicationController
  before_filter :require_admin, :only => [:destroy]
  before_filter :load_movie

  # GET /comments
  # GET /comments.xml
  def index
    @comments = @movie.comments.find(:all, 
      :limit => params[:limit], 
      :order => 'created_at DESC'
    )
    recent_comments = lambda do |options| 
      options[:builder] << @comments.to_xml(:skip_instruct => true) 
    end

    respond_to do |format|
      format.html # index.html.erb
      format.xml  { render :xml => @movie.to_xml(
        :procs => [recent_comments]
      ) }
    end
  end

  # POST /comments
  # POST /comments.xml
  def create
    @comment = @movie.comments.build(params[:comment])

    respond_to do |format|
      if @comment.save
        flash[:notice] = 'Comment was successfully created.'
        format.html { redirect_to(movie_path(@movie)) }
        format.xml  { redirect_to :back }
      else
        format.html { render :template => "movies/show" }
        format.xml  { redirect_to :back }
      end
    end
  end

  # DELETE /comments/1
  # DELETE /comments/1.xml
  def destroy
    @comment = @movie.comments.find(params[:id])
    @comment.destroy

    respond_to do |format|
      format.html { redirect_to(movie_comments_path(@movie)) }
      format.xml  { head :ok }
    end
  end
  
  private
  def load_movie
    @movie = if params[:movie_id] 
      Movie.find(params[:movie_id])
    elsif params[:title]
      Movie.find_by_title(params[:title])
    end
  end
end
