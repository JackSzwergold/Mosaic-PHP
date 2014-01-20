# branch: Set the github branch that will be used for this deployment.
# server: The name of the destination server you will be deploying to.
# web_builds: The directory on the server into which the actual source code will deployed.
# live_root: The live directory which the current version will be linked to.

set :branch, "master"

server "www.preworn.com", :app, :web, :db, :primary => true
set :web_builds, "#{deployment_root}/builds"
set :live_root, "#{deployment_root}/www.preworn.com"

set :deploy_to, "#{web_builds}/#{application}/production"

# Remote caching will keep a local git repository on the server you're deploying to and
# simply run a fetch from that rather than an entire clone. This is probably the best
# option as it will only fetch the changes since the last deploy.
set :deploy_via, :remote_cache

# Disable warnings about the absence of the styleseheets, javscripts & images directories.
set :normalize_asset_timestamps, false

after "deploy:create_symlink" do
  # Link "current" into the web root
  run "cd #{live_root} && if [ -h site ]; then rm site; fi && ln -sf #{current_path} ./site"
end

after "deploy:update", "deploy:cleanup"