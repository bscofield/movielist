class ReleasesController < ApplicationController
  before_filter :require_admin, :except => :index
  before_filter :load_movies_options, :only => [:new, :edit, :create, :update]
  
  # GET /releases
  # GET /releases.xml
  def index
    respond_to do |format|
      format.html { 
        paginate_releases
      }
      format.iphone { 
        paginate_releases
      }
      format.js {
        @releases = Release.upcoming(params)
      }
      format.xml  { 
        @releases = Release.upcoming(params)
        render :xml => @releases.to_xml(:dasherize => false, :include => :movie)
      }
    end
  end

  # GET /releases/new
  # GET /releases/new.xml
  def new
    @release = Release.new

    respond_to do |format|
      format.html # new.html.erb
      format.xml  { render :xml => @release }
    end
  end

  # GET /releases/1/edit
  def edit
    @release = Release.find(params[:id])
  end

  # POST /releases
  # POST /releases.xml
  def create
    @release = Release.new(params[:release])

    respond_to do |format|
      if @release.save
        flash[:notice] = 'Release was successfully created.'
        format.html { redirect_to(releases_path) }
        format.xml  { render :xml => @release, :status => :created, :location => @release }
      else
        format.html { render :action => "new" }
        format.xml  { render :xml => @release.errors, :status => :unprocessable_entity }
      end
    end
  end

  # PUT /releases/1
  # PUT /releases/1.xml
  def update
    @release = Release.find(params[:id])

    respond_to do |format|
      if @release.update_attributes(params[:release])
        flash[:notice] = 'Release was successfully updated.'
        format.html { redirect_to(releases_path) }
        format.xml  { head :ok }
      else
        format.html { render :action => "edit" }
        format.xml  { render :xml => @release.errors, :status => :unprocessable_entity }
      end
    end
  end

  # DELETE /releases/1
  # DELETE /releases/1.xml
  def destroy
    @release = Release.find(params[:id])
    @release.destroy

    respond_to do |format|
      format.html { redirect_to(releases_url) }
      format.xml  { head :ok }
    end
  end
  
  private
  def paginate_releases
    @releases = Release.paginate(:all, :page => params[:page], :include => :movie)
  end

  def load_movies_options
    @movies = Movie.find(:all, :order => 'title').map {|m| [m.title, m.id]}
  end
end
