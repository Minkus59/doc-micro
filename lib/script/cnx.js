function verifPseudo(email)
{
   if(email.value.length < 2 || email.value.length > 25)
   {
      surligne(email, true);
      return false;
   }
   else
   {
      surligne(email, false);
      return true;
   }
}