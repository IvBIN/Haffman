<?php
class Coder
{
    private $message;
    private $letters;
    private $lettersUniq = [];

    private $tree;
    private $table = [];
    public function __construct($message)
    {
        $message = trim($message);
        $this->message = $message;
        $this->letters = preg_split(
            "//u",
            mb_strtolower($message),
            -1,
            PREG_SPLIT_NO_EMPTY
        );
    }
    public function encode()
    {
        $lettersCount = [];
        foreach ($this->letters as $letter){
//            $lettersCount[$letter]['count']++;
//            $lettersCount[$letter]['letter']= $letter;

            if(!empty($lettersCount[$letter])){
                $lettersCount[$letter]->count++;
            }
            else{
                $lettersCount[$letter] = new Tree($letter,1);
                $this->lettersUniq[] = $letter;
            }
        }
        usort($lettersCount, function ($a,$b){
            return $b->count <=> $a->count;
        });
        while (count($lettersCount) > 1){
            $rightChild = array_pop($lettersCount);
            $leftChild = array_pop($lettersCount);
            $tree = new Tree(
                $leftChild->symbols . $rightChild ->symbols,
                $leftChild->count + $rightChild ->count,
                $leftChild,
                $rightChild
            );
            $lettersCount[] = $tree;
            usort($lettersCount, function ($a,$b){
                return $b->count <=> $a->count;
            });
        }
        $this->tree = $lettersCount[0];
        foreach ($this->lettersUniq as $letter){
            $code ='';
            $this->tree->getCode($letter,$code);
            $this->table[$letter] = [
                'letter' => $letter,
                'code' => $code
            ];
        }
        $result = '';
        foreach ($this->letters as $letter){
            $result .= $this->table[$letter]['code'];
        }
        return $result;
    }
    public function getTable()
    {
        $str ="
            <table style='border-collapse: collapse'>
                <tr>
                    <th style='border: 1px solid black'>Буква</th>
                    <th style='border: 1px solid black'>Код</th>
                </tr>
        ";
        foreach ($this->table as $item){
            $str .= "
                <tr>
                  <td style='border: 1px solid black'>{$item['letter']}</td>  
                  <td style='border: 1px solid black'>{$item['code']}</td>  
                </tr>
            ";
        }
        return $str .= '</table>';
    }
    public function decode($code)
    {
      $arrayCode =preg_split(
          '//u',
          $code,
          -1,
          PREG_SPLIT_NO_EMPTY
      );
      $letter = '';
      while (!empty($arrayCode)){
          $letter .= $this->tree->getLetter($arrayCode);
      }
      return $letter;
    }
}
