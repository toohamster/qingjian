<?php

class Feed {

    protected $entries = array();

    protected $title;
    protected $url;
    protected $updated;
    protected $author;

    public function title($title) {
        $this->title = $title;
        return $this;
    }

    public function url($url) {
        $this->url = $url;
        return $this;
    }

    public function updated($updated) {
        $this->updated = $updated;
        return $this;
    }

    public function author($author) {
        $this->author = $author;
        return $this;
    }

    public function addEntry($entry) {
        $this->entries[] = $entry;
        return $this;
    }    

    public function __toString() {
        $result = '<?xml version="1.0">';
        $result .= '<feed xmlns="http://www.w3.org/2005/Atom">';
        $result .= '<title>' . $this->title . '</title>';
        $result .= '<link href=">' . $this->url . '" />';
        $result .= '<updated>' . $this->updated . '</updated>';
        $result .= '<author>' . $this->author . '</author>';

        foreach ($this->entries as $entry) {
            $result .= $entry;
        }

        return $result;
    }
}

class Entry {
    protected $title;
    protected $url;
    protected $updated;
    protected $author;
    protected $description;

    public function title($title) {
        $this->title = $title;
        return $this;
    }

    public function url($url) {
        $this->url = $url;
        return $this;
    }

    public function updated($updated) {
        $this->updated = $updated;
        return $this;
    }

    public function author($author) {
        $this->author = $author;
        return $this;
    }

    public function description($description) {
        $this->description = $description;
        return $this;
    }

    public function __toString() {
        $result .= '<entry>';
        $result .= '<title>' . $this->title . '</title>';
        $result .= '<link href=">' . $this->url . '" />';
        $result .= '<updated>' . $this->updated . '</updated>';
        $result .= '<author>' . $this->author . '</author>';
        $result .= '<description>' . $this->description . '</description>';
        $result .= '</entry>';
        return $result;
    }
}
?>