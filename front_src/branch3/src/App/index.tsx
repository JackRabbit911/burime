import { useEffect } from "react";
import Form from "./components/Form"
import { $bootstrap, appStarted } from "store/bootstrap";
import { useUnit } from "effector-react";
import { bootstrapSch } from "schema/input";

const App = () => {
  const bootstrap = useUnit($bootstrap)
  
  useEffect(() => {
    appStarted()
  }, [])
  
  if (bootstrap) {
      const valid = bootstrapSch.safeParse(bootstrap)

      if (!valid.success) {
        console.log(valid.error)
        return 'Invalid input data'
      }
  }

  return bootstrap ? (
    <>
      <Form bootstrap={bootstrap}/>
    </>
  ) : 'загрузка';
};

export default App;


