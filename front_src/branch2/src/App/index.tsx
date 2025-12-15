import { useEffect } from "react";

import { useUnit } from "effector-react";

import { appStarted, $bootstrapStatus, $isBranchLoaded } from "store";
import Loader from "reused/Loading";
import Form from "./components/Form";
import StatusErrorMessage from "./components/StatusErrorMessage";
import Dialog from "reused/Dialog";

const App = () => {
  const [status, isBranchLoaded] = useUnit([$bootstrapStatus, $isBranchLoaded]);

  useEffect(() => {
    appStarted()
  }, []);

  if (status >= 400) {
    return (
      <StatusErrorMessage status={status} />
    );
  }

  if (!isBranchLoaded) {
    return <Loader />;
  }

  return (
    <>
      <Form />
      <Dialog />
    </>
  );
};

export default App;
