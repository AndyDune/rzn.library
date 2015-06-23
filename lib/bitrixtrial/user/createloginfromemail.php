<?
namespace Rzn\Library\BitrixTrial\User;
class CreateLoginFromEmail
{
    protected $_email = '';
    protected $_login = '';
    public function __construct($email)
    {
        $this->_login = $this->_email = strtolower($email);
        $this->_process();
    }

    public function get()
    {
        return $this->_login;
    }

    protected function _process()
    {
        $pos = strpos($this->_login, "@");
        if ($pos !== false)
            $this->_login = $_POST["NEW_LOGIN"] = substr($this->_login, 0, $pos);

        if (strlen($this->_login) > 47)
            $this->_login = $_POST["NEW_LOGIN"] = substr($this->_login, 0, 47);

        if (strlen($this->_login) < 3)
            $this->_login .= "_";

        if (strlen($this->_login) < 3)
            $this->_login .= "_";

        $dbUserLogin = \CUser::GetByLogin($this->_login);
        if ($arUserLogin = $dbUserLogin->Fetch())
        {
            $newLoginTmp = $this->_login;
            $uind = 0;
            do
            {
                $uind++;
                if ($uind == 10)
                {
                    $this->_login = $this->_email;
                    $newLoginTmp = $this->_login;
                }
                elseif ($uind > 10)
                {
                    $this->_login = "buyer".time().GetRandomCode(2);
                    $newLoginTmp = $this->_login;
                    break;
                }
                else
                {
                    $newLoginTmp = $this->_login . $uind;
                }
                $dbUserLogin = \CUser::GetByLogin($newLoginTmp);
            }
            while ($arUserLogin = $dbUserLogin->Fetch());

            $this->_login = $newLoginTmp;
        }
    }
}