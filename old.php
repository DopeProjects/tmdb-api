    function get_movie_info($tmdb_id='')
    {
      if($tmdb_id =='' || $tmdb_id==NULL):
        $tmdb_id  = '00000000';
      endif;
      $data           = file_get_contents('http://xxxxxxxxx.net/scrapper/v20/get_movie_json/none/'.$tmdb_id);
      $data           = json_decode($data, true);
      if(isset($data['error_message'])){
        $response['status']    = 'fail';
      }else{
        $actors         = array();
        $directors      = array();
        $writters       = array();
        $countries      = array();
        $genres         = array();
        if(count($data) >0){
          $a          = $this->update_actors($data['credits']['cast']);
          $dw         = $this->update_directors_writers($data['credits']['crew']);

          $actors        = $this->filter_actors($data['credits']['cast']);
          $directors     = $this->filter_directors($data['credits']['crew']);
          $writters      = $this->filter_writters($data['credits']['crew']);
          $countries     = $this->filter_countries($data['production_countries']);
          $genres        = $this->filter_genres($data['genres']);
        }
        $response      = array();
        if(count($data) >0 && $data['title'] !='' && $data['title'] !=NULL){
          $response['status']         = 'success';
          $response['imdbid']         = $data['imdb_id'];//$data['imdbID'];
          $response['title']          = $data['title'];
          $response['plot']           = $data['overview'];
          $response['runtime']        = $data['runtime'].' Min';
          $response['actor']          = $actors;//$this->common_model->get_star_ids('actor',$data['Actors']);
          $response['director']       = $directors;//$this->common_model->get_star_ids('director',$data['Director']);
          $response['writer']         = $writters;//$this->common_model->get_star_ids('writer',$data['Writer']);
          $response['country']        = $countries;//$this->common_model->get_country_ids($data['Country']);
          $response['genre']          = $genres;//$this->common_model->get_genre_ids($movie->getGenres());
          $response['rating']         = $data['vote_average'];
          $response['release']        = $data['release_date'];
          $response['thumbnail']      = 'https://image.tmdb.org/t/p/w185/'.$data['poster_path'];
          $response['poster']         = 'https://image.tmdb.org/t/p/w780/'.$data['backdrop_path'];
        }else{
          $response['status']    = 'fail';
        }
      }
    return $response;
  }
