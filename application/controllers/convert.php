<?php

class Convert_Controller extends Base_Controller {

	public function get_index()
	{
		$this->layout->content = 'Convert';
	}

	public function get_hymnal()
	{
		$songs = Gvyp_Song::all();

		foreach ($songs as $song)
		{
			// Insert song
			$song_id = DB::connection('sqlite_hymn')
				->table('songs')
				->insert_get_id(array(
					'number' => $song->number,
				));

			// Insert stanzas
			foreach ($song->stanzas as $stanza)
			{
				DB::connection('sqlite_hymn')
					->table('stanzas')
					->insert(array(
						'number' => $stanza->number,
						'song_id' => $song_id,
						'song_number' => $song->number,
						'is_chorus' => (bool) $stanza->is_chorus,
						'text' => $stanza->text,
					));
			}

			$this->layout->content = 'Done';
		}
	}

}