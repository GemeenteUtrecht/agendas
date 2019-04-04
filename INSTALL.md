# INSTALLATION

This repository is optimized for docker and includes a docker-compose file, the preferred way of getting a local instance up and running would therefore be docker or docker desktop. You can get Docker Desktop for windows [here](https://docs.docker.com/docker-for-windows/) (or [here](https://docs.docker.com/docker-for-mac/) for mac users). 

Now we just need a local copy of the code base, you can just download it [here](https://github.com/GemeenteUtrecht/trouwen/archive/master.zip) but the preferred way would be trough a git client (like [Source tree](https://www.sourcetreeapp.com/) or [Git Kraken](https://www.gitkraken.com/). Or if you prefer command line you can create a new folder and gitclone the project into it (you might want to replace my_branche with your name):

```
$ git clone https://github.com/GemeenteUtrecht/trouwen.git -b my_branche
```

After that we only need to spin up the local containers, you can skip the pull but that would force a local build and take considerably longer.

```
$ docker-compose pull # Download the latest versions of the pre-built images
$ docker-compose up -d # Running in detached mode
```
