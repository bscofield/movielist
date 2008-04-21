class MoviesController < ApplicationController
  before_filter :require_admin, :except => [:index, :show]
  before_filter :load_people_options, :only => [:new, :edit, :create, :update]

  # GET /movies
  # GET /movies.xml
  def index
    unless params[:query].blank?
      query = ['CONCAT(title, description) LIKE ?', "%#{params[:query]}%"]
    end
    @movies = Movie.paginate(:all, :page => params[:page], :conditions => query)

    respond_to do |format|
      format.html # index.html.erb
      format.xml  { render :xml => @movies }
    end
  end

  # GET /movies/1
  # GET /movies/1.xml
  def show
    @movie = Movie.find(params[:id])

    respond_to do |format|
      format.html # show.html.erb
      format.xml  { render :xml => @movie }
    end
  end

  # GET /movies/new
  # GET /movies/new.xml
  def new
    @movie = Movie.new
    @people = Person.find(:all, :order => 'last_name, first_name').map do |p|
      ["#{p.last_name}, #{p.first_name}", p.id]
    end

    respond_to do |format|
      format.html # new.html.erb
      format.xml  { render :xml => @movie }
    end
  end

  # GET /movies/1/edit
  def edit
    @movie = Movie.find(params[:id])
    @people = Person.find(:all, :order => 'last_name, first_name').map do |p|
      ["#{p.last_name}, #{p.first_name}", p.id]
    end
  end

  # POST /movies
  # POST /movies.xml
  def create
    @movie = Movie.new(params[:movie])

    respond_to do |format|
      if @movie.save
        flash[:notice] = 'Movie was successfully created.'
        format.html { redirect_to(@movie) }
        format.xml  { render :xml => @movie, :status => :created, :location => @movie }
      else
        format.html { render :action => "new" }
        format.xml  { render :xml => @movie.errors, :status => :unprocessable_entity }
      end
    end
  end

  # PUT /movies/1
  # PUT /movies/1.xml
  def update
    @movie = Movie.find(params[:id])

    respond_to do |format|
      if @movie.update_attributes(params[:movie])
        flash[:notice] = 'Movie was successfully updated.'
        format.html { redirect_to(@movie) }
        format.xml  { head :ok }
      else
        format.html { render :action => "edit" }
        format.xml  { render :xml => @movie.errors, :status => :unprocessable_entity }
      end
    end
  end

  # DELETE /movies/1
  # DELETE /movies/1.xml
  def destroy
    @movie = Movie.find(params[:id])
    @movie.destroy

    respond_to do |format|
      format.html { redirect_to(movies_url) }
      format.xml  { head :ok }
    end
  end

  private
  def load_people_options
    @people = Person.find(:all, :order => 'last_name, first_name').map do |p|
      ["#{p.last_name}, #{p.first_name}", p.id]
    end
  end
end
