import { useEffect } from "react";
import Form from "./components/Form"
import { $bootstrap, $bootstrapStatus, appStarted } from "store/bootstrap";
import { useUnit } from "effector-react";
import { bootstrapSch } from "schema/input";
import Loader from "reused/Loading";
import ErrorCmp from "reused/ErrorCmp";

const App = () => {
  const bootstrap = useUnit($bootstrap)
  const status = useUnit($bootstrapStatus)
  
  useEffect(() => {
    appStarted()
  }, [])
  
  if (bootstrap) {
      const valid = bootstrapSch.safeParse(bootstrap)

      if (!valid.success) {
        console.log(valid.error)
        return <Loader message='Invalid input data' />
      }
  }

  if (status >= 400) {
    return (
        <ErrorCmp status={status} />
    )
  }

  return bootstrap ? (
    <>
      <Form bootstrap={bootstrap}/>
    </>
  ) : <Loader message="Загрузка" />
};

export default App;


